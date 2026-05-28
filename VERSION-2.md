# Version 2 Roadmap

## Revenue & Core

- [ ] **Payment integration (Stripe/Paddle)** — Plans are defined in config (free/pro/enterprise) but no checkout flow exists. This is the single biggest revenue unlock.
- [ ] **Server-side PDF generation** — `dompdf` is installed but unused. Replace `window.print()` with proper server-side PDF generation for consistent output with A4 page breaks and embedded fonts.
- [X] **Evaluate your own CVs** — Allow clicking "Evaluate" on a builder-created CV directly instead of requiring export-to-PDF then re-upload. The evaluator should accept a Cv model ID.
- [ ] **Credit purchase flow** — Add one-time credit pack purchases (e.g., 50 credits for $5). The `purchase_bonus` config key already exists for this.

## Builder UX

- [ ] **CV duplication/cloning** — Add a "Duplicate" button on the drafts page so users can create variations for different job applications.
- [ ] **Inline preview pane** — Embed the template preview side-by-side in the builder instead of requiring navigation to a separate `/preview` route.
- [X] **Autosave** — Periodically save form state to prevent data loss from accidental navigation.
- [X] **Drag-and-drop reordering** — Allow reordering experiences, skills, and other list items.
- [ ] **CV deletion** — Users can create and edit CVs but cannot delete them.
- [ ] **Profile photo upload** — The Creative template has a placeholder circle but there is no photo upload field in personal info.

## AI Enhancements

- [ ] **Edit/Delete AI tools** — The AI can only ADD entries. Add update and delete tools for all sections (experiences, skills, educations, etc.).
- [ ] **Inline "AI Improve" buttons** — Add a sparkle button next to each section that pre-fills the AI chat with contextual improvement prompts.
- [ ] **Streaming AI responses** — Stream AI chat responses token-by-token instead of showing them all at once.
- [ ] **Role-specific quick prompts** — Replace hardcoded software engineering prompts with dynamically generated ones based on the user's CV data.
- [ ] **Job description matching score** — Dedicated page where users paste a job description and get a compatibility score with keyword suggestions.

## Templates

- [ ] **Color/font customization** — Accent color picker and font family selector stored as JSON on the Cv model.
- [ ] **Section visibility & ordering** — Toggle sections on/off and drag to reorder. Store config as JSON on the Cv model.
- [ ] **More templates** — Add industry-specific templates: Academic, Creative Portfolio, Federal/Government, Healthcare, Finance.
- [ ] **Fix `technologies` field** — The `technical-ats` template renders `$exp->technologies` but there is no UI to edit it in the experience manager.

## Ecosystem

- [ ] **Public CV sharing** — Generate shareable links with optional password protection and expiry dates.
- [ ] **CV versioning** — Track change history with the ability to revert to previous versions.
- [ ] **Evaluation-to-builder feedback loop** — After evaluation, add "Fix this" buttons that jump to the relevant builder section with AI suggestions pre-loaded.
- [ ] **Evaluation trend charts** — Show score progression over time instead of limiting comparison to 2 evaluations.

## Future (V2+)

- [ ] **AI Interviewer** — Simulate a job interview based on the user's CV and a target job description. The AI asks role-specific questions, evaluates responses, and provides feedback on communication, technical depth, and confidence.
- [ ] **Custom template from image** — Upload a CV/resume design image and use a vision model to analyze its layout, colors, fonts, and structure, then generate a matching Blade template the user can use and customize.

## Polish

- [ ] **Send contact form emails** — The form validates but never actually sends or stores the message.
- [ ] **Generalize AWS-specific fields** — Fields like `is_aws_service`, `aws_metadata`, `aws_services_used` are hardcoded. Hide behind a toggle or support multiple cloud providers.
- [ ] **Remove dead `CvImporter` component** — Duplicated import logic, not routed, and missing features compared to the builder's built-in import.
