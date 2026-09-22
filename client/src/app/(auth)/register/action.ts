"use server";

import { redirect } from "next/navigation";
import { register } from "@/features/auth/api";
import { ApiError } from "@/lib/api";
import type { RegisterPayload } from "@/features/auth/types";

export interface RegisterFormState {
  message?: string;
  errors?: {
    name?: string;
    email?: string;
    password?: string;
    password_confirmation?: string;
    role?: string;
  };
}

export async function registerAction(
  _prevState: RegisterFormState,
  formData: FormData,
): Promise<RegisterFormState> {
  const name = String(formData.get("name") ?? "").trim();
  const email = String(formData.get("email") ?? "").trim();
  const password = String(formData.get("password") ?? "");
  const passwordConfirmation = String(
    formData.get("password_confirmation") ?? "",
  );
  const role = String(formData.get("role") ?? "");

  const errors: RegisterFormState["errors"] = {};
  if (!name) errors.name = "Name is required.";
  if (!email) errors.email = "Email is required.";
  if (!password) errors.password = "Password is required.";
  if (password.length > 0 && password.length < 8) {
    errors.password = "Password must be at least 8 characters.";
  }
  if (passwordConfirmation !== password) {
    errors.password_confirmation = "Passwords don't match.";
  }
  if (role !== "dosen" && role !== "mahasiswa") {
    errors.role = "Choose a role.";
  }

  if (Object.keys(errors).length > 0) {
    return { errors };
  }

  const payload: RegisterPayload = {
    name,
    email,
    password,
    password_confirmation: passwordConfirmation,
    role: role as RegisterPayload["role"],
  };

  try {
    await register(payload);
  } catch (error) {
    if (error instanceof ApiError) {
      if (error.status === 422) {
        return {
          message: error.message,
          errors: {
            name: error.fieldError("name"),
            email: error.fieldError("email"),
            password: error.fieldError("password"),
            role: error.fieldError("role"),
          },
        };
      }

      return { message: error.message };
    }

    return {
      message:
        "Couldn't create your account — check your connection and try again.",
    };
  }

  redirect("/dashboard");
}
