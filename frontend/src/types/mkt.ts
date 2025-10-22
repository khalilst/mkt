export interface MeasurementSet {
  id: number;
  title: string;
  mkt?: number;
  created_at: string;
}

export interface Measurement {
  id: number;
  measured_at: string;
  temperature: number;
}
