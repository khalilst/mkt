export const useFormatDate = () => {
  const formatDate = (date: string) => new Date(date).toLocaleString();
  return { formatDate };
};
