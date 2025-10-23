import api from "@/services/api";
import { endpoints } from "@/shared/endpoints";
import { useRouter } from "vue-router";
import { pathKeys } from "@/shared/routes";
import { toast } from "vue3-toastify";
import { ref } from "vue";

interface MeasurementSetUploadData {
  title: string;
  measurementsFile?: File | null;
}

const useMeasurementSetUploadForm = () => {
  const router = useRouter();

  const form = ref<MeasurementSetUploadData>({ title: "" });
  const loading = ref(false);

  const selectFile = (e: Event) => {
    const target = e.target as HTMLInputElement;
    form.value.measurementsFile = target.files?.[0] || null;
  };

  const submit = async () => {
    const { title, measurementsFile } = form.value;

    const formData = new FormData();
    formData.append("title", title);
    if (measurementsFile) formData.append("measurementsFile", measurementsFile);

    loading.value = true;
    try {
      const { data } = await api.post(
        endpoints.mkt.measurementSetStore,
        formData
      );

      router.push({
        name: pathKeys.measurementSetShow.name,
        params: { id: data.id },
      });

      toast.success("Saved successfully");
    } catch (error: any) {
      toast.error(error?.message || "Something went wrong");
    } finally {
      loading.value = false;
    }
  };

  return {
    selectFile,
    submit,
    loading,
    form,
  };
};

export default useMeasurementSetUploadForm;
