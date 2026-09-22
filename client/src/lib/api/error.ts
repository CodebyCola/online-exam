type LaravelValidationErrors = Record<string, string[]>;

export class ApiError extends Error {
  status: number;
  errors: LaravelValidationErrors | null;
  body: unknown;

  constructor(
    message: string,
    status: number,
    errors: LaravelValidationErrors | null = null,
    body: unknown = null
  ) {
    super(message);
    this.name = "ApiError";
    this.status = status;
    this.errors = errors;
    this.body = body;
  }

  // helper ambil pesan error pertama untuk field tertentu, cocok buat form
  fieldError(field: string): string | undefined {
    return this.errors?.[field]?.[0];
  }
}