import { ApiError } from "./error";
import { getXsrfToken } from "./csrf";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

export async function apiFetch<T = unknown>(
  path: string,
  options: RequestInit = {}
): Promise<T> {
  const res = await fetch(`${API_URL}${path}`, {
    ...options,
    credentials: "include",
    headers: {
      "content-type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getXsrfToken() ?? "",
      ...options.headers,
    },
  });

  const contentType = res.headers.get("content-type");
  const hasJsonBody = contentType?.includes("application/json");
  const body = hasJsonBody ? await res.json().catch(() => null) : null;

  if (!res.ok) {
    if (res.status === 422 && body?.errors) {
      throw new ApiError(body.message ?? "Validasi gagal", 422, body.errors, body);
    }
    if (res.status === 419) {
      throw new ApiError("Sesi kadaluarsa, silakan coba lagi", 419, null, body);
    }
    if (res.status === 401) {
      throw new ApiError(body?.message ?? "Belum login", 401, null, body);
    }
    if (res.status === 403) {
      throw new ApiError(body?.message ?? "Tidak punya akses", 403, null, body);
    }
    if (res.status === 404) {
      throw new ApiError(body?.message ?? "Data tidak ditemukan", 404, null, body);
    }
    if (res.status >= 500) {
      throw new ApiError("Terjadi kesalahan di server", res.status, null, body);
    }
    throw new ApiError(body?.message ?? `Request gagal (${res.status})`, res.status, null, body);
  }

  return body as T;
}