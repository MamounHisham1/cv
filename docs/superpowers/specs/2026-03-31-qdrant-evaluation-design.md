# RAG-Powered CV Evaluation Design

## Summary

Integrate Qdrant vector search into the AI CV evaluation feature so the `CvEvaluatorAgent` can search for similar resumes and past evaluations before producing its structured evaluation. The agent receives two search tools and is **required** to use them before evaluating, producing more informed and benchmarked results. The integration is invisible to the user and does not change pricing.

## Approach

**RAG Agent with tools** — the `CvEvaluatorAgent` gets search tools it calls autonomously. The agent is instructed to always search before evaluating, making the tool usage mandatory rather than optional.

## Architecture

### Data Flow

```
User submits CV
  -> CvEvaluator extracts text
  -> CvEvaluatorAgent receives text + tools
  -> Agent calls SearchResumes (finds similar high-quality resumes)
  -> Agent calls SearchEvaluations (finds patterns in past evaluations)
  -> Agent uses search results as benchmark
  -> Agent produces structured evaluation
  -> CvEvaluator saves to DB
  -> CvEvaluator embeds and stores evaluation in Qdrant
```

### Components to Change

| Component | Change |
|-----------|--------|
| `CvEvaluatorAgent` | Add `SearchResumes` + `SearchEvaluations` tools; update instructions to mandate search usage |
| `CvEvaluator` (Livewire) | After saving evaluation to DB, embed and upsert into Qdrant `cv_evaluations` collection |
| New `EvaluationVectorStore` service | Handles the `cv_evaluations` Qdrant collection (ensure collection, store, search). Separate class from `ResumeVectorStore` to keep concerns distinct. |
| New `SearchEvaluations` AI tool | Wraps evaluation vector search, scoped to CvEvaluatorAgent only |
| New artisan command | `evaluations:vectorize` to backfill existing evaluations into Qdrant |

### Components Unchanged

| Component | Reason |
|-----------|--------|
| `SearchResumes` tool | Already exists, already used by CvBuilderAgent, reuse as-is |
| `ResumeVectorStore` | Handles `resume_samples` collection, no changes needed |
| `cv_evaluations` DB table | No schema changes |
| Credit system | No pricing changes |
| UI / Blade views | Invisible to user, no view changes |

## Qdrant Collections

### Collection 1: `resume_samples` (existing)

- Already populated with imported resume data
- Payload: `role`, `source`, `content`
- 768-dimensional vectors, cosine similarity, `mxbai-embed-large` via Ollama
- Used by `SearchResumes` tool (shared across features)

### Collection 2: `cv_evaluations` (new)

- Stores embeddings of past user CV evaluations
- Payload: `user_id`, `grade`, `overall_score`, `content` (truncated CV text)
- Same embedding model: `mxbai-embed-large` (768-dim, cosine) for consistency
- Used **only** by `SearchEvaluations` tool, scoped to the evaluation feature
- Populated after each evaluation completes; backfilled via artisan command

## Agent Instructions

The `CvEvaluatorAgent` instructions will be updated to:

1. **Mandate** the agent to always call search tools before evaluating — not optional
2. Use `SearchResumes` to find similar high-quality resumes for the same role
3. Use `SearchEvaluations` to find patterns in past evaluations (common strengths/weaknesses)
4. Benchmark each criterion score against the reference material
5. Reference specific strengths from top matches when identifying what the CV does well
6. Reference weaknesses relative to strong examples when identifying improvements
7. If searches return empty results (Qdrant unavailable), fall back to standard evaluation

## Error Handling

- Qdrant unreachable or embedding failure: evaluation proceeds without reference data (pure LLM fallback)
- Search tools return empty results on failure; agent instructions tell it to evaluate without references
- No user-facing errors from Qdrant failures (invisible to user)
- Logging for debugging: log warnings when Qdrant operations fail

## Testing

- Unit tests for `EvaluationVectorStore` — store, search, ensure collection
- Unit test for `SearchEvaluations` AI tool
- Feature test for updated `CvEvaluator` — verify evaluation still completes when Qdrant is unavailable
- Feature test verifying evaluation gets stored in Qdrant after completion
- Test for `evaluations:vectorize` artisan command
