export const MeasurementSetStatus = {
  InProgress: 0,
  Completed: 1,
  Failed: 2,
} as const

export type MeasurementSetStatus =
  (typeof MeasurementSetStatus)[keyof typeof MeasurementSetStatus]

export interface MeasurementSet {
  id: number;
  title: string;
  mkt?: number;
  status?: MeasurementSetStatus;
  created_at: string;
}

export interface Measurement {
  id: number;
  measured_at: string;
  temperature: number;
}
