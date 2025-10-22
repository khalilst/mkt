import { pathKeys } from "@/shared/routes";
import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    ...pathKeys.measurementSetsIndex,
    component: () => import("@/pages/MeasurementSetsIndex.vue"),
  },
  {
    ...pathKeys.measurementSetShow,
    component: () => import("@/pages/MeasurementSetShow.vue"),
  },
  {
    ...pathKeys.uploadMeasurements,
    component: () => import("@/pages/UploadMeasurements.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
