import * as yup from 'yup'

export const measurementSetSchema = yup.object({
  title: yup.string().required().max(255),
  file: yup.mixed().required('File is required'),
})
