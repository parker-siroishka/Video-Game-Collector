import axios from "axios";

const getGamePlaySessions = async (gameId) => {
    let game_id = gameId ? gameId : -1;
    try {
        const { data } = await axios.get(
            route("gamePlaySessions.get", game_id)
        );
        return data;
    } catch (error) {
        console.error("Error fetching game data:", error);
        throw error; // Re-throw the error to handle it in the calling function
    }
};

export default getGamePlaySessions;
