"use server";

import { redirect } from "next/navigation";
import { login } from "@/features/auth/api";
import { ApiError } from "@/lib/api";

export interface LoginFormState {
  message?: string;
  errors?: {
    email?: string;
    password?: string;
  };
}

export async function loginAction(
  _prevState: LoginFormState,
  formData: FormData,
): Promise<LoginFormState> {
  const email = String(formData.get("email") ?? "").trim();
  const password = String(formData.get("password") ?? "");

  if (!email || !password) {
    return {
      errors: {
        email: !email ? "Email is required." : undefined,
        password: !password ? "Password is required." : undefined,
      },
    };
  }

  try {
    await login({ email, password });
  } catch (error) {
    if (error instanceof ApiError) {
      if (error.status === 422) {
        return {
          message: error.message,
          errors: {
            email: error.fieldError("email"),
            password: error.fieldError("password"),
          },
        };
      }

      return { message: error.message };
    }

    return { message: "Couldn't sign in — check your connection and try again." };
  }

  redirect("/dashboard");
}