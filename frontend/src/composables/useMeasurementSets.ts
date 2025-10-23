import { onMounted, ref } from "vue";
import api from "@/services/api";
import type { MeasurementSet } from "@/types/mkt";
import { type PaginationMeta } from "@/types/pagination";
import usePagination from "./usePagination";
import { endpoints } from "@/shared/endpoints";

const useMeasurementSets = () => {
  const measurementSets = ref<MeasurementSet[]>([]);

  const fetchSets = async (): Promise<PaginationMeta> => {
    const {
      data: { items, limit, page, total, pages },
    } = await api.get(endpoints.mkt.measurementSetIndex, {
      params: {
        limit: paginationMeta.value.limit,
        page: paginationMeta.value.page,
      },
    });

    measurementSets.value = items;
    return { limit, page, total, pages };
  };

  const { paginationMeta, loading, next, prev, fetchPage } =
    usePagination(fetchSets);
    
  onMounted(fetchPage);

  return {
    measurementSets,
    paginationMeta,
    loading,
    next,
    prev,
  };
};

export default useMeasurementSets;
