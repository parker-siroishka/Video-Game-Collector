import axios from "axios";

const getGamePlaySessions = async (id) => {
    try {
        const { data } = await axios.get(route("gamePlaySessions.get", id));
        return data;
    } catch (error) {
        console.error("Error fetching game data:", error);
        throw error; // Re-throw the error to handle it in the calling function
    }
};

export default getGamePlaySessions;
