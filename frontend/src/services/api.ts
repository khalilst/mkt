import { API_BASE_URL } from "@/shared/endpoints";
import axios from "axios";

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 8000,
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      const { status, data } = error.response;

      // Symfony validation error (422)
      if (status === 422) {
        const message = data.errors.join("\n");
        console.error("Validation errors:", message);
        throw new Error(message);
      }
    }

    console.error("Network error:", error);
    throw error;
  }
);

export default api;
