import router from "@/router";
import { API_BASE_URL } from "@/shared/endpoints";
import { pathKeys } from "@/shared/routes";
import axios from "axios";

declare module "axios" {
  export interface AxiosRequestConfig {
    redirectOn404?: boolean;
  }
}

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 8000,
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      const {
        response: { status, data },
        config,
      } = error;

      // Symfony validation error (422)
      if (status === 422) {
        const message = data.errors.join("\n");
        console.error("Validation errors:", message);
        throw new Error(message);
      }

      if (status === 404 && config?.redirectOn404) {
        router.push({ name: pathKeys.notFound.name });
      }
    }

    console.error("Network error:", error);
    throw error;
  }
);

export default api;
