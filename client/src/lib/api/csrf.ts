const API_URL = process.env.NEXT_PUBLIC_API_URL;

// panggil csrf cookie milik sanctum dulu
export async function getCsrfCookie() {
  await fetch(`${API_URL}/sanctum/csrf-cookie`, {
    credentials: "include",
  });
}

// baca xsrf token dari cookie yang dikirim oleh laravel
export function getXsrfToken() {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  return match ? decodeURIComponent(match[1]) : null;
}