"use client";

import { useActionState, useState } from "react";
import Link from "next/link";
import { registerAction, type RegisterFormState } from "./action";

const initialState: RegisterFormState = {};

export default function RegisterPage() {
    const [state, formAction, pending] = useActionState(registerAction, initialState);
    const [role, setRole] = useState<"dosen" | "mahasiswa" | "">("");

    return (
        <div className="card">
            <h1 style={{ marginBottom: "var(--space-2)" }}>Create an account</h1>
            <p className="text-caption" style={{ color: "var(--foreground-subtle)", marginBottom: "var(--space-6)" }}>
                Set up your ExamGuard account.
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
                    <label htmlFor="name" className="field-label">
                        Name
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        autoComplete="name"
                        required
                        className="input"
                        aria-invalid={state.errors?.name ? "true" : undefined}
                        aria-describedby={state.errors?.name ? "name-error" : undefined}
                    />
                    {state.errors?.name && (
                        <p id="name-error" className="field-error">
                            {state.errors.name}
                        </p>
                    )}
                </div>

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

                <div style={{ marginBottom: "var(--space-4)" }}>
                    <label htmlFor="password" className="field-label">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autoComplete="new-password"
                        required
                        className="input"
                        aria-invalid={state.errors?.password ? "true" : undefined}
                        aria-describedby={state.errors?.password ? "password-error" : "password-hint"}
                    />
                    {state.errors?.password ? (
                        <p id="password-error" className="field-error">
                            {state.errors.password}
                        </p>
                    ) : (
                        <p id="password-hint" className="field-hint">
                            At least 8 characters.
                        </p>
                    )}
                </div>

                <div style={{ marginBottom: "var(--space-6)" }}>
                    <label htmlFor="password_confirmation" className="field-label">
                        Confirm password
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autoComplete="new-password"
                        required
                        className="input"
                        aria-invalid={state.errors?.password_confirmation ? "true" : undefined}
                        aria-describedby={state.errors?.password_confirmation ? "password-confirmation-error" : undefined}
                    />
                    {state.errors?.password_confirmation && (
                        <p id="password-confirmation-error" className="field-error">
                            {state.errors.password_confirmation}
                        </p>
                    )}
                </div>

                <fieldset
                    style={{ border: "none", padding: 0, margin: "0 0 var(--space-6) 0" }}
                    aria-describedby={state.errors?.role ? "role-error" : undefined}
                >
                    <legend className="field-label" style={{ padding: 0 }}>
                        I am a
                    </legend>
                    <div style={{ display: "flex", flexDirection: "column", gap: "var(--space-3)" }}>
                        <label className="answer-option" data-selected={role === "mahasiswa"}>
                            <input
                                type="radio"
                                name="role"
                                value="mahasiswa"
                                checked={role === "mahasiswa"}
                                onChange={() => setRole("mahasiswa")}
                                required
                            />
                            Mahasiswa — I&apos;m taking exams
                        </label>
                        <label className="answer-option" data-selected={role === "dosen"}>
                            <input
                                type="radio"
                                name="role"
                                value="dosen"
                                checked={role === "dosen"}
                                onChange={() => setRole("dosen")}
                            />
                            Dosen — I&apos;m building and reviewing exams
                        </label>
                    </div>
                    {state.errors?.role && (
                        <p id="role-error" className="field-error">
                            {state.errors.role}
                        </p>
                    )}
                </fieldset>

                <button type="submit" className="btn btn-primary" style={{ width: "100%" }} disabled={pending}>
                    {pending ? "Creating account…" : "Create account"}
                </button>
            </form>

            <p className="text-caption" style={{ color: "var(--foreground-subtle)", marginTop: "var(--space-6)" }}>
                Already have an account? <Link href="/login">Sign in</Link>
            </p>
        </div>
    );
}