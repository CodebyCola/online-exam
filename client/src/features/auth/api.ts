import { apiFetch} from "@/lib/api";
import type { LoginPayload, RegisterPayload, User } from "@/features/auth/types";

export async function login(payload: LoginPayload) {
  const user = await apiFetch<User>("/auth/login", {
    method: "POST",
    body: JSON.stringify(payload),
  });

  return user;
}


export async function register(payload: RegisterPayload) {
  const user = await apiFetch<User>("/auth/register", {
    method: "POST",
    body: JSON.stringify(payload),
  });

  return user;
}
