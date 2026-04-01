# New CV Template Designs

**Date:** 2026-04-01
**Status:** Approved

## Overview

Add 5 new CV templates to the builder, expanding the lineup from 5 to 10. Each new template fills a gap in the current offering: no warm colors, no timeline layout, no grid-based typographic style, no light sidebar alternative, and no compact density option.

## Existing Templates (for reference)

| Template | Layout | Font | Color |
|----------|--------|------|-------|
| Professional Classic | Single-column | Serif | Gray/black |
| Technical ATS | Two-column dark sidebar | Sans | Teal + slate dark |
| Modern Minimal | Single-column | Sans | Gray only |
| Creative | Two-column dark sidebar | Sans | Teal + slate dark |
| Executive | Single-column centered | Serif | Gray/black |

## New Templates

### 1. Bold

- **Slug:** `bold`
- **Layout:** Single-column with full-width indigo-700 header band
- **Font:** Sans-serif (`font-sans`)
- **Colors:** Indigo-700 header, indigo-50 pill backgrounds, indigo-200 borders, white body
- **Header:** Colored band with white text — name (3xl bold), title in indigo-200, contact row in indigo-100
- **Skills:** Grouped by category with category labels, rendered as indigo-tinted pills
- **Section headings:** `text-sm font-bold uppercase tracking-wider text-indigo-700`
- **Technologies:** Shown as indigo-50 pill badges under each experience entry
- **Bottom layout:** Certifications and Languages side-by-side in 2-column grid
- **Print:** Header band uses `print-color-adjust: exact`
- **Icon:** `bolt`

### 2. Timeline

- **Slug:** `timeline`
- **Layout:** Single-column with vertical timeline for experience
- **Font:** Sans-serif (`font-sans`)
- **Colors:** Gray monochrome, gray-800 first dot with ring highlight, gray-300 connector line, gray-400 subsequent dots
- **Header:** Name in bold + light weight split, title in gray-500, contact row with gray-500 text
- **Experience:** Vertical timeline with `ml-3` left offset, `w-px bg-gray-300` connector line, 9px round dots (first is larger with ring), dates above each entry
- **Skills:** Rounded pills (`bg-gray-100 text-gray-700 text-xs rounded-full`)
- **Section headings:** `text-xs font-bold uppercase tracking-widest text-gray-400` (same as Modern Minimal)
- **Technologies:** Gray-100 pill badges under experience entries
- **Bottom layout:** Certifications and Languages in 2-column grid
- **Icon:** `clock`

### 3. Swiss

- **Slug:** `swiss`
- **Layout:** 1/3 + 2/3 grid (not flex sidebar), single-column body
- **Font:** Sans-serif (`font-sans`)
- **Colors:** Red-600 accent (top bar, section borders, dates), gray-900 text, gray-400 secondary text
- **Header:** Extra-large name in two weights — first name `font-black`, last name `font-light text-gray-400`, both uppercase. Title in red-600. Contact below a thick `border-t-2 border-gray-900` rule.
- **Top accent:** 2px red-600 bar across full width
- **Section headings:** `text-xs font-bold uppercase tracking-widest text-red-600` with red-600 bottom border
- **Left column:** Skills (name + level), Languages, Certifications, Education
- **Right column:** Profile summary, Experience, Projects
- **Print:** Red bar uses `print-color-adjust: exact`
- **Icon:** `grid-3x3`

### 4. Warm

- **Slug:** `warm`
- **Layout:** Two-column — 1/3 light amber sidebar + 2/3 white main content
- **Font:** Sans-serif (`font-sans`)
- **Colors:** Amber-50 sidebar bg, amber-500 dots/accents, amber-200 borders, amber-600 headings, amber-700 title text, amber-800 initials
- **Sidebar:** Initials circle (amber-200 bg), name + title, contact info, skills with 4-dot level indicators (amber-500 filled, amber-200 empty), languages, certifications
- **Main content:** Summary, Experience (amber left border `border-l-2 border-amber-200`), Education, Projects (also amber left border)
- **Section headings:** `text-sm font-bold uppercase tracking-wider text-amber-600` with small amber-500 accent bar
- **Print:** Sidebar and dots use `print-color-adjust: exact`
- **Icon:** `sun`

### 5. Compact

- **Slug:** `compact`
- **Layout:** Single-column, maximum content density
- **Font:** Serif (`font-serif`)
- **Colors:** Gray-900 text, gray-300 section borders, gray-400/500 secondary
- **Base size:** `font-size: 10px; line-height: 1.4` on outer wrapper
- **Header:** Right-aligned contact info beside the name (flex justify-between), title in italic gray-600
- **Skills:** Inline dot-separated list (`·` separator), no badges
- **Experience:** Title + company + location on one line, dates right-aligned, description and achievements compact
- **Education + Certifications:** Side-by-side in 2-column grid
- **Languages:** Single line with `·` separator after heading
- **Section headings:** `font-bold uppercase tracking-wide border-b border-gray-300` at 11px
- **Icon:** `document-chart-bar`

## Integration Steps (per template)

1. Create Blade template at `resources/views/cv/templates/{slug}.blade.php`
   - Convert preview PHP to Blade syntax
   - Use `$cv` variable with Eloquent relationships
   - Follow data access patterns from skill spec
2. Register in `getAvailableTemplates()` in `app/Livewire/CvBuilder.php`
3. Add thumbnail wireframe in onboarding `@switch` block in `resources/views/livewire/cv-builder.blade.php`

## Constraints

- All templates must use `max-w-[210mm]` A4 wrapper
- Dark/colored backgrounds need `print-color-adjust: exact`
- Use Tailwind utility classes only (no custom CSS except inline styles for font-size/print)
- Each template has its own hardcoded color palette
- All content comes from `$cv` variable — no hardcoded text
