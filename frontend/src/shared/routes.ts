export const pathKeys = {
    measurementSetsIndex: {name: 'measurementSetsIndex', path: '/'},
    measurementSetShow: {name: 'measurementSetShow', path: '/sets/:id'},
    uploadMeasurements: {name: 'uploadMeasurements', path: '/upload'},
    notFound: {name: 'notFound', path: '/:pathMatch(.*)*'}
} as const;
