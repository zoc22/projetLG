import { useQuery } from "@tanstack/react-query";
import { api } from "@/src/core/api/client";

const fetchWebsites = async () => {
  const { data } = await api.get("/affiliation/dashboard"); // Reusing dashboard for now since it returns websites
  return data.websites;
};

export const useWebsites = () => {
  return useQuery({
    queryKey: ["websites"],
    queryFn: fetchWebsites,
  });
};
