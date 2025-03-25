import axios from "axios";

const getRecentPlaySessions = async () => {
    try {
        const { data } = await axios.get(route("playSessions.get", -1));
        // Sort the data by created_at in descending order
        data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        // Filter out duplicates based on the data.game.id property
        const uniqueData = Array.from(
            new Set(data.map((item) => item.game.id))
        ).map((gameId) => data.find((item) => item.game.id === gameId));

        return uniqueData.slice(0, 10);
    } catch (error) {
        console.error("Error fetching game data:", error);
        throw error; // Re-throw the error to handle it in the calling function
    }
};

export default getRecentPlaySessions;
