import axios from "axios";

const getUniqueConsoles = async () => {
    const { data } = await axios.get(route("games.getUniqueConsoles"));
    return data.sort((a, b) => a.console.localeCompare(b.console));
};

export default getUniqueConsoles;
