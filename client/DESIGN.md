# ExamGuard — Design System

**Version 1.0 · Mobile-first · Written for the whole team (design, frontend, backend)**

This document is the single source of truth for how ExamGuard looks, reads, and behaves. `global.css` implements every token defined here. If code and this document disagree, this document wins — fix the code.

---

## 1. Why this system exists

ExamGuard is not a marketing product. It is an instrument two kinds of people rely on under very different conditions:

| Who | State of mind | What they need from the interface |
|---|---|---|
| **Dosen** (lecturer) | Calm, administrative, building and reviewing | Clarity, low friction, confidence that data is saved and correct |
| **Mahasiswa** (student) | High-stress, time-boxed, one shot at this | Zero ambiguity, zero lag, absolute trust in the clock and the submit button |

Every design decision in this document is filtered through one question: **does this make the system more legible and more trustworthy, or does it just make it prettier?** If a choice only does the second, cut it.

The product's own engineering values — server-authoritative time, transaction-safe submission, AI as *assistive not authoritative* — should be felt in the interface, not just true in the backend. A student should be able to look at the timer and feel "this can't be wrong." A lecturer should be able to look at an AI-suggested score and instantly understand "this is a suggestion I must approve, not a fact."

---

## 2. Design principles

1. **The clock is sacred.** Anywhere a countdown, timestamp, or duration appears, it must be rendered in the monospace numeral style, must never visually shift or reflow, and must never be the last thing repainted on a slow connection.
2. **State AI's role visually, every time.** An AI-suggested score is never styled identically to a final, dosen-approved score. The distinction (see §7.6) is not optional and not a single color swap — it's a full visual pattern (icon + label + treatment) so it can never be mistaken at a glance.
3. **Mobile is the primary canvas, not a breakpoint.** Every screen is designed at 375px first. Desktop is an expansion of that layout, not a separate design.
4. **One accent, spent on purpose.** Brass (§3) appears only where focus, urgency, or time-pressure is real. It is never decorative. If brass shows up twice on a calm screen, that's a bug.
5. **Never let the interface lie about state.** Loading, saving, saved, error, expired, locked — these states exist because the underlying system genuinely has them (queues, jobs, transactions). The UI must always reflect real backend state, never an optimistic guess that could mislead a student about whether their answer was actually saved.
6. **No decoration without information.** Borders, dividers, numbering, and labels always encode something true about the content's structure — never applied for visual rhythm alone.

---

## 3. Color

### 3.1 Palette rationale

Exam software should feel like a well-run testing room: paper, ink, a clock on the wall — not a startup dashboard. The palette avoids both common AI-generated defaults (warm cream + terracotta; near-black + neon accent) in favor of a cooler, quieter "paper and ink" register, with a single warm accent reserved entirely for time and focus.

### 3.2 Core tokens

| Token | Hex | Use |
|---|---|---|
| `--ink-950` | `#14181F` | Primary text, headings |
| `--ink-700` | `#3A4150` | Secondary text |
| `--ink-500` | `#63697A` | Tertiary text, placeholders, disabled labels |
| `--ink-300` | `#9CA1AF` | Disabled text, icon-inactive |
| `--paper-0` | `#FFFFFF` | Cards, inputs, elevated surfaces |
| `--paper-50` | `#F7F6F2` | App background |
| `--paper-100` | `#EFEDE6` | Recessed surfaces (code blocks, quoted answers) |
| `--line-200` | `#DEDBD1` | Default borders, dividers |
| `--line-300` | `#C7C3B6` | Stronger borders (inputs on focus-adjacent, table rules) |
| `--brass-600` | `#8A5D22` | Brass text on light backgrounds (AA contrast) |
| `--brass-500` | `#A6742C` | Brass primary — timers, focus rings, active tab |
| `--brass-100` | `#F1E4CC` | Brass background tint (timer chip background) |
| `--signal-green-700` | `#175E3C` | Success text |
| `--signal-green-600` | `#1F7A4D` | Success icons/borders |
| `--signal-green-100` | `#DCEEE3` | Success background tint |
| `--signal-red-700` | `#8C2C22` | Danger/error text |
| `--signal-red-600` | `#B23A2E` | Danger icons/borders, destructive actions |
| `--signal-red-100` | `#F5DFDB` | Danger background tint |
| `--signal-blue-700` | `#1E4E7A` | Informational text (in-progress, async states) |
| `--signal-blue-600` | `#2B6CA3` | Informational icons/borders |
| `--signal-blue-100` | `#DDEAF3` | Informational background tint |

### 3.3 Color usage rules

- **Brass is reserved.** It appears only on: the exam timer, the active state of a keyboard focus ring, the "in progress" tab/step indicator, and the primary CTA on the student exam-taking screen. It never appears as a generic link color, generic hover color, or in the lecturer dashboard chrome.
- **Green never means "AI thinks this is correct."** Green is reserved for dosen-approved, finalized states only (`score` column, not `ai_suggested_score`). See §7.6.
- **Red is reserved for real danger:** expired exams, destructive actions (delete exam, discard attempt), and validation errors. Do not use red for neutral "closed" or "draft" states — use `--ink-500` with a neutral badge instead.
- **Blue signals "the system is doing something asynchronously."** Use for "Grading in progress," "Auto-submit pending," queued states — anything where the backend job hasn't resolved yet.
- Never communicate meaning by color alone — every colored state also carries an icon and a text label (WCAG 1.4.1, and see §9).

### 3.4 Dark mode

Dark mode is supported via `prefers-color-scheme` and a manual `data-theme` override (see `global.css` §Theming). Token names stay identical; only values swap. Brass, green, red, and blue are each re-tuned for dark backgrounds to hold the same relative contrast and the same *meaning*, not the same hex.

---

## 4. Typography

### 4.1 Type families

| Role | Family | Fallback stack |
|---|---|---|
| UI & reading text | **Source Sans 3** | `"Source Sans 3", ui-sans-serif, system-ui, -apple-system, sans-serif` |
| Numerals that must be trusted at a glance | **IBM Plex Mono** | `"IBM Plex Mono", ui-monospace, "SFMono-Regular", Menlo, monospace` |

**Why two families, and why these two:** Source Sans 3 is a humanist grotesque built for long-form legibility at UI sizes with full tabular-figure support — it reads like well-set exam paper, not like a SaaS product. IBM Plex Mono is reserved *exclusively* for numbers whose exact value carries stakes: the countdown timer, exam codes, scores, question point-values in the builder, and timestamps in audit logs. This isn't a stylistic flourish — tabular, fixed-width digits mean a timer's digits never shift width as they tick (a proportional font's "1" is narrower than its "8," causing visible jitter), and it visually marks these numbers as "system-verified, not decorative," which reinforces the product's core trust claim.

Never use Plex Mono for prose. Never use Source Sans 3 for the countdown timer.

### 4.2 Type scale (mobile-first)

Scale follows a ~1.2 ratio (minor third), tuned by hand at the extremes for a working tool rather than a display site.

| Token | Mobile (375px base) | Desktop (≥768px) | Weight | Line height | Use |
|---|---|---|---|---|---|
| `--text-display` | 28px / 1.75rem | 36px / 2.25rem | 600 | 1.2 | Page-level H1 only (one per screen) |
| `--text-title` | 22px / 1.375rem | 26px / 1.625rem | 600 | 1.25 | Section headers, card titles |
| `--text-subtitle` | 17px / 1.0625rem | 18px / 1.125rem | 600 | 1.35 | Sub-sections, question stems |
| `--text-body` | 16px / 1rem | 16px / 1rem | 400 | 1.55 | Default paragraph, form labels, answer options |
| `--text-body-sm` | 14px / 0.875rem | 14px / 0.875rem | 400 | 1.5 | Helper text, metadata, table cells |
| `--text-caption` | 12px / 0.75rem | 12px / 0.75rem | 500 | 1.4 | Timestamps, badges, field hints |
| `--text-numeral-lg` | 32px / 2rem | 44px / 2.75rem | 600 | 1.1 | The exam timer (see §7.1) |
| `--text-numeral-md` | 20px / 1.25rem | 22px / 1.375rem | 600 | 1.2 | Scores, exam codes |
| `--text-numeral-sm` | 14px / 0.875rem | 14px / 0.875rem | 500 | 1.4 | Inline timestamps, table numerals |

Body text never sits below 16px on mobile — this is a hard floor, not a guideline, since it also prevents iOS Safari's automatic zoom-on-focus for form inputs.

### 4.3 Line length & rhythm

- Reading content (question stems, essay rubrics, instructions) is capped at `65ch` max-width.
- Paragraph spacing uses `--space-4` (see §5) between blocks, never margin-collapsing hacks.
- Letter-spacing stays at the font's default (`0`) for body text. Do not tighten or loosen headings — this is a legibility tool, not a display face.
- Do not set UI labels in all-caps. Use sentence case with weight/size for hierarchy instead (see `frontend-design` guidance this system follows).

---

## 5. Spacing & sizing

8px base grid. All spacing tokens are multiples of 4px for the two smallest steps (fine adjustments), 8px beyond that.

| Token | Value | Typical use |
|---|---|---|
| `--space-1` | 4px | Icon-to-label gap, tight inline spacing |
| `--space-2` | 8px | Input internal padding (vertical), chip padding |
| `--space-3` | 12px | Form field gap, compact card padding |
| `--space-4` | 16px | Default stack spacing, card padding (mobile) |
| `--space-5` | 20px | Section internal padding |
| `--space-6` | 24px | Card padding (desktop), gap between form groups |
| `--space-8` | 32px | Gap between page sections |
| `--space-10` | 40px | Page top padding (mobile) |
| `--space-12` | 48px | Page top padding (desktop) |
| `--space-16` | 64px | Major section breaks on long pages (results, builder) |

### Touch targets

Minimum interactive target: **44×44px**, no exceptions — this holds even for icon-only buttons in dense lecturer tables. On the student exam-taking screen, primary targets (answer options, Submit) are **48×48px minimum** because these are used under time pressure, sometimes on a phone.

---

## 6. Layout

### 6.1 Grid

- **Mobile (< 640px):** single column, `16px` side margins, full-width cards and inputs.
- **Tablet (640–1023px):** single column content, max-width `640px`, centered; lecturer tables may go full-width with horizontal scroll for dense data.
- **Desktop (≥ 1024px):** 12-column grid, `24px` gutters, max content width `1200px`. Lecturer dashboard uses a fixed left sidebar (240px) + fluid content. Student exam-taking screen stays intentionally narrow (max `720px`) even on desktop — extra width does not help someone answer a question faster, and constraint reduces eye travel under time pressure.

All layout is **left-aligned**. This is a working tool, not a landing page — nothing here is center-aligned marketing copy.

### 6.2 Breakpoints

```css
--bp-sm: 640px;   /* large phones, small tablets */
--bp-md: 768px;   /* tablets */
--bp-lg: 1024px;  /* small laptops */
--bp-xl: 1280px;  /* desktop */
```

Design and build mobile-first: base styles target 375px, then layer `min-width` media queries upward. Never design desktop-first and squeeze down.

### 6.3 Elevation

Flat by default. Shadows are used sparingly and only to indicate *temporary* elevation above the page (modals, the sticky timer bar, dropdowns) — never as decoration on static cards. Two shadow tokens only:

```css
--shadow-sm: 0 1px 2px rgba(20, 24, 31, 0.06), 0 1px 1px rgba(20, 24, 31, 0.04);
--shadow-md: 0 4px 12px rgba(20, 24, 31, 0.10), 0 2px 4px rgba(20, 24, 31, 0.06);
```

Do not invent a third shadow value for a one-off component. If `--shadow-md` isn't enough, the problem is probably borders/contrast, not shadow depth.

### 6.4 Radius

```css
--radius-sm: 6px;   /* inputs, chips, small buttons */
--radius-md: 10px;  /* cards, modals */
--radius-lg: 14px;  /* the timer bar, hero-level containers */
--radius-full: 999px; /* pills, avatars, status dots */
```

Radius scales with the size of the element it's applied to — this is a deliberate hierarchy signal (bigger container, slightly bigger radius), not one border-radius value slapped on everything.

---

## 7. Core components & patterns

This section covers the components specific to ExamGuard's domain. Generic components (buttons, inputs, etc.) are defined as CSS utility classes in `global.css` directly.

### 7.1 The exam timer

The single most trust-critical element in the product. Rules:

- Always rendered in `--text-numeral-lg` / IBM Plex Mono, `font-variant-numeric: tabular-nums`.
- Format: `MM:SS` under one hour, `H:MM:SS` at or above one hour. Never reflow between formats mid-exam in a way that shifts layout — reserve width for the longer format from the start if the exam duration could cross the hour mark.
- Background: `--brass-100` chip with `--brass-600` text at normal time; **never green** (green is reserved for approved/graded states, not "time remaining is fine" — conflating the two teaches students to associate green with "safe" in the wrong context).
- Under 5 minutes remaining: background shifts to `--signal-red-100`, text to `--signal-red-700`, and a static (non-flashing) warning icon appears. **Do not flash, pulse, or animate the timer** at any threshold — a student under exam stress does not need an anxiety-inducing blinking countdown; a clear color-and-icon state change communicates urgency without inducing panic.
- The timer is computed from the server-provided `expires_at`, re-synced on visibility change (tab refocus) and reconnect. The UI must never let the timer's displayed value silently drift from server truth — if sync fails, show a small "reconnecting" indicator, not a frozen or guessed timer.
- Sticky on mobile: pinned to the top of the viewport during an active attempt, above safe-area insets, always visible without scrolling.

### 7.2 Question card

- One question per card, `--radius-md`, `--paper-0` background, `--line-200` border (1px).
- Question number + point value shown as metadata (`--text-caption`, `--ink-500`), point value in Plex Mono since it's a number that matters for the student's strategy (e.g., "Question 3 of 10 · 5 pts").
- Multiple choice options are full-width tap targets (≥48px height), not small radio buttons with tiny labels — the entire row is tappable.
- Essay questions show a live word/character counter in `--text-caption`, Plex Mono, bottom-right of the textarea.
- Autosave indicator (see §7.5) lives at the bottom of every question card, not just globally — a student should be able to tell *this specific answer* saved, not just infer it from a global toast that may have scrolled away.

### 7.3 Exam status badge

Used everywhere an exam's lifecycle state appears (dosen list, student list, results).

| Status | Background | Text | Icon |
|---|---|---|---|
| Draft | `--paper-100` | `--ink-500` | pencil |
| Published / Open | `--signal-green-100` | `--signal-green-700` | dot (solid) |
| Closed | `--paper-100` | `--ink-700` | lock |
| In progress (student's own attempt) | `--brass-100` | `--brass-600` | clock |
| Grading in progress | `--signal-blue-100` | `--signal-blue-700` | spinner (static icon, not literal spin, unless `prefers-reduced-motion` allows it) |
| Result finalized | `--signal-green-100` | `--signal-green-700` | check |

Badges always pair color with icon + text label — never color alone (see §3.3, §9).

### 7.4 Submission confirmation

Because submission is irreversible and transaction-guarded on the backend, the frontend must match that seriousness:

- Manual submit always requires a confirmation step (modal or dedicated confirm screen) — never a single accidental tap submits an exam.
- Confirmation copy states the concrete consequence, not a vague "Are you sure?" — see `global.css` companion copy guidance and §8.
- After submit succeeds, show an explicit, unambiguous success state (not just a redirect) — the student came from a state of anxiety and needs a clear "this is done and it worked" moment before the UI moves on.
- If auto-submit fires (job-triggered, not user-initiated), the student's next view must clearly disclose *that it was auto-submitted*, not manual — this is a transparency commitment, not just a data point (`submission_type` exists in the schema specifically so the UI can be honest about this).

### 7.5 Autosave indicator

Appears inline near the content it describes (per-answer, per-form), three states only:

1. **Saving** — `--ink-500` text, small static spinner icon, label "Saving…"
2. **Saved** — `--signal-green-700` text, check icon, label "Saved" — fades to a neutral `--ink-500` "Saved just now" / relative timestamp after 2s so green doesn't linger and lose meaning
3. **Save failed** — `--signal-red-700` text, warning icon, label "Couldn't save — retrying" with an automatic retry, and after repeated failure, an explicit manual "Retry now" action. Never fail silently.

### 7.6 AI-suggested vs. final score (critical pattern)

This is the most important trust pattern in the lecturer-facing UI and must be implemented identically everywhere a score appears:

- **AI-suggested score**: shown in `--signal-blue-100` background chip, `--signal-blue-700` text, Plex Mono, always prefixed with a small "AI" tag-icon and the word "Suggested" — e.g. `AI Suggested: 7.5 / 10`. Always shown alongside the AI's rubric-based justification text (`ai_feedback`), never as a bare number.
- **Final/approved score**: shown in `--signal-green-100` background chip, `--signal-green-700` text, Plex Mono, no "AI" marker — e.g. `Final: 8 / 10`. Only appears once a dosen has explicitly approved or edited the value.
- **Never render an unapproved AI score using the green "final" treatment.** This is a hard rule, not a style preference — it is the visual expression of "AI assistive, not authoritative" and directly supports the product's core credibility claim. A code review or design review should treat any violation of this rule as a bug, not a nitpick.
- The essay grading review screen always shows both values side by side when they differ, so the dosen can see exactly what they changed and why (audit trail, per the `exam_activity_logs` design).

### 7.7 Empty states

Every empty state follows: **what this is → why it's empty → how to start**, in the interface's plain voice (see §8). No decorative illustrations that don't carry information — a single relevant icon at `--ink-300` is enough.

---

## 8. Voice & content principles

*(Full copy library and message-by-message audit lives alongside this document; these are the standing rules.)*

- **Plain, active, specific.** "Save changes," not "Submit." "Delete this exam," not "Are you sure?"
- **Actions keep their name through the whole flow.** A button that says "Publish" produces a confirmation that says "Published" — never "Submit" → "Success!"
- **Errors explain what happened and how to fix it**, in the interface's voice, never apologetic, never vague. Structure: *what happened + why + how to fix.*
- **Time-critical copy is short.** On the exam-taking screen especially, every extra word is something a stressed student has to parse under a ticking clock. Prefer "5 minutes left" over "You currently have approximately 5 minutes remaining to complete this exam."
- **Never blame the student for system behavior.** If an exam auto-submits because time ran out, the copy states the fact plainly ("Time's up — your exam was submitted automatically") without implying carelessness.
- **AI copy is precise about certainty.** Never "The AI graded your essay." Always "AI suggested a score — your lecturer will review and finalize it."

---

## 9. Accessibility (non-negotiable baseline)

- Color contrast: body text ≥ 4.5:1, large text (`--text-title` and above) ≥ 3:1, tested against both light and dark token sets.
- Every interactive element has a visible keyboard focus state using `--brass-500` (the one place brass appears outside timers, and it's consistent — focus is itself a kind of "active/urgent" state).
- All status/color communication is paired with an icon and a text label — never color alone.
- `prefers-reduced-motion` is respected everywhere; the only intentional motion in the product is functional (answer selection feedback, save-state transitions), never decorative entrance animations.
- Touch targets ≥ 44×44px (≥48×48px on the exam-taking flow, per §5).
- Timer and autosave states are announced to screen readers via `aria-live="polite"` regions — a screen reader user must be able to perceive "5 minutes left" and "answer saved" without hunting for them.
- Forms: every input has a real, associated `<label>` — never placeholder-as-label.

---

## 10. File & token governance

- All tokens live in `global.css` as CSS custom properties under `:root` (light) and are overridden under the dark-mode selectors — see that file's header comment for the exact mechanism.
- Component-specific CSS (question cards, timer, badges) lives in `global.css` as utility/component classes so both the Next.js client and any future server-rendered emails/exports can share one visual language.
- New colors, type sizes, or spacing values are **never** hand-picked ad hoc in a component. If the system doesn't have the value you need, add it here first, with a rationale, then implement it.
