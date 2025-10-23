export const useFormatDate = () => {
  const formatDate = (date: string) => {
    const d = new Date(date);
    const pad = (n: number) => n.toString().padStart(2, "0");
    return (
      `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ` +
      `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`
    );
  };
  return { formatDate };
};
