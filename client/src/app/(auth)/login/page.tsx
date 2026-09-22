"use client";

import { useActionState } from "react";
import Link from "next/link";
import { loginAction, type LoginFormState } from "./action";

const initialState: LoginFormState = {};

export default function LoginPage() {
  const [state, formAction, pending] = useActionState(loginAction, initialState);

  return (
    <div className="card">
      <h1 style={{ marginBottom: "var(--space-2)" }}>Sign in</h1>
      <p className="text-caption" style={{ color: "var(--foreground-subtle)", marginBottom: "var(--space-6)" }}>
        Enter your ExamGuard account details.
      </p>

      {state.message && (
        <div
          role="alert"
          className="field-error"
          style={{
            background: "var(--signal-red-100)",
            padding: "var(--space-3) var(--space-4)",
            borderRadius: "var(--radius-sm)",
            marginBottom: "var(--space-4)",
          }}
        >
          {state.message}
        </div>
      )}

      <form action={formAction} noValidate>
        <div style={{ marginBottom: "var(--space-4)" }}>
          <label htmlFor="email" className="field-label">
            Email
          </label>
          <input
            id="email"
            name="email"
            type="email"
            autoComplete="email"
            required
            className="input"
            aria-invalid={state.errors?.email ? "true" : undefined}
            aria-describedby={state.errors?.email ? "email-error" : undefined}
          />
          {state.errors?.email && (
            <p id="email-error" className="field-error">
              {state.errors.email}
            </p>
          )}
        </div>

        <div style={{ marginBottom: "var(--space-6)" }}>
          <label htmlFor="password" className="field-label">
            Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            autoComplete="current-password"
            required
            className="input"
            aria-invalid={state.errors?.password ? "true" : undefined}
            aria-describedby={state.errors?.password ? "password-error" : undefined}
          />
          {state.errors?.password && (
            <p id="password-error" className="field-error">
              {state.errors.password}
            </p>
          )}
        </div>

        <button type="submit" className="btn btn-primary" style={{ width: "100%" }} disabled={pending}>
          {pending ? "Signing in…" : "Sign in"}
        </button>
      </form>

      <p className="text-caption" style={{ color: "var(--foreground-subtle)", marginTop: "var(--space-6)" }}>
        Don&apos;t have an account? <Link href="/register">Create one</Link>
      </p>
    </div>
  );
}