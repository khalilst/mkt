import { ref } from "vue";
import { type PaginationMeta, type PaginationQuery } from "@/types/pagination";

const usePagination = (useQuery: PaginationQuery) => {
  const loading = ref(false);
  const error = ref<string | null>(null);

  const paginationMeta = ref<PaginationMeta>({ limit: 10, page: 1 });

  const fetchPage = async () => {
    loading.value = true;
    error.value = null;

    try {
      paginationMeta.value = await useQuery();
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

    fetchPage();
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

    fetchPage();
  };

  return {
    paginationMeta,
    loading,
    next,
    prev,
  };
};

export default usePagination;
