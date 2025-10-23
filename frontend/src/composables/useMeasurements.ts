import { onMounted, ref } from "vue";
import api from "@/services/api";
import type { Measurement, MeasurementSet } from "@/types/mkt";
import { type PaginationMeta } from "@/types/pagination";
import usePagination from "./usePagination";
import { endpoints } from "@/shared/endpoints";

const useMeasurements = (measurementSet: MeasurementSet) => {
  const measurements = ref<Measurement[]>([]);

  const fetchMeasurements = async (): Promise<PaginationMeta> => {
    const {
      data: { items, limit, page, total, pages },
    } = await api.get(endpoints.mkt.measurementIndex(measurementSet.id), {
      params: {
        limit: paginationMeta.value.limit,
        page: paginationMeta.value.page,
      },
    });

    measurements.value = items;
    return { limit, page, total, pages };
  };

  const { paginationMeta, loading, next, prev, fetchPage } =
    usePagination(fetchMeasurements);

  onMounted(fetchPage);

  return {
    measurements,
    paginationMeta,
    loading,
    next,
    prev,
  };
};

export default useMeasurements;
