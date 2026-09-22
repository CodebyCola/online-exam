import { apiFetch, getCsrfCookie } from "@/lib/api";
import type { LoginPayload, RegisterPayload, User } from "@/features/auth/types";

export async function login(payload: LoginPayload) {
  // untuk method update (POST/PUT/DELETE) perlu ambil csrf dlu
  await getCsrfCookie();

  const user = await apiFetch<User>("/auth/login", {
    method: "POST",
    body: JSON.stringify(payload),
  });

  return user;
}


export async function register(payload: RegisterPayload) {
  // untuk method update (POST/PUT/DELETE) perlu ambil csrf dlu
  await getCsrfCookie();

  const user = await apiFetch<User>("/auth/register", {
    method: "POST",
    body: JSON.stringify(payload),
  });

  return user;
}
