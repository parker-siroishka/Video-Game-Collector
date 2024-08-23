import axios from "axios";

const getGames = async () => {
    const { data } = await axios.get(route("games.get"));
    return data.sort((a, b) => a.title.localeCompare(b.title));
};

export default getGames;
