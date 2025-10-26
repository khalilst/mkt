export const EVENT_BASE_URL = 'http://localhost:3001/.well-known/mercure';

export const topics = {
  mkt: {
    calculated: (id: number) => {
        const url = new URL(EVENT_BASE_URL);
        url.searchParams.append('topic', `/measurement-sets/${id}`);

        return url;
    },
  },
} as const;
