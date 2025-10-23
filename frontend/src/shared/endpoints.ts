export const API_BASE_URL = 'http://localhost:8000/api';

export const endpoints = {
  mkt: {
    measurementSetIndex: '/measurement-sets',
    measurementSetShow: (id: number) => `/measurement-sets/${id}`,
    measurementSetStore: '/measurement-sets',
    measurementIndex: '/measurement-sets/{id}/measurement',
  },
} as const;
