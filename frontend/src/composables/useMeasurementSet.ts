import { onMounted, ref } from "vue";
import api from "@/services/api";
import type { MeasurementSet } from "@/types/mkt";
import { endpoints } from "@/shared/endpoints";
import { topics } from "@/shared/events";

const useMeasurementSet = (id: number) => {
  const set = ref<MeasurementSet | null>(null);

  const subscribeMktCalculatedEvent = () => {
    if (set?.value?.status) {
      return;
    }

    const eventSource = new EventSource(topics.mkt.calculated(id));

    eventSource.onmessage = (event) => {
      const { mkt, status } = JSON.parse(event.data);

      if (set.value) {
        set.value.mkt = mkt;
        set.value.status = status;
      }
      eventSource.close();
    };
  };

  const loadSet = async () => {
    const { data } = await api.get(endpoints.mkt.measurementSetShow(id), {
      redirectOn404: true,
    });
    set.value = data;
    subscribeMktCalculatedEvent();
  };

  onMounted(loadSet);

  return {
    set,
    loadSet,
  };
};

export default useMeasurementSet;
