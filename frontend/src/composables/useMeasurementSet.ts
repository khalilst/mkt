import { ref } from "vue";
import api from "@/services/api";
import type { MeasurementSet } from "@/types/mkt";
import { type PaginationMeta } from "@/types/pagination";

export function useMeasurementSets() {
  const measurementSets = ref<MeasurementSet[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  const paginationMeta = ref<PaginationMeta>({ limit: 10, page: 1 });

  const fetchSets = async () => {
    loading.value = true;
    error.value = null;

    try {
      const {
        data: { items, limit, page, total, pages },
      } = await api.get("/measurement-sets", {
        params: {
          limit: paginationMeta.value.limit,
          page: paginationMeta.value.page,
        },
      });

      measurementSets.value = items;
      paginationMeta.value = { limit, page, total, pages };
    } finally {
      loading.value = false;
    }
  };

  const next = () => {
    const { page, pages } = paginationMeta.value;

    if (page === pages) {
        return;
    }

    paginationMeta.value = {
      ...paginationMeta.value,
      page: page + 1,
    };

    fetchSets();
  };

  const prev = () => {
    const { page } = paginationMeta.value;

    if (page === 1) {
        return;
    }

    paginationMeta.value = {
      ...paginationMeta.value,
      page: page - 1,
    };

    fetchSets();
  };

  return {
    measurementSets,
    paginationMeta,
    loading,
    fetchSets,
    next,
    prev,
  };
}
