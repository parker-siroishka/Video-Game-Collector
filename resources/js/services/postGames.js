import axios from "axios";

const postGames = async (form) => {
    try {
        const response = await axios.post(route("games.post"), {
            title: form.title,
            coverart: form.thumbnailUrl,
            playtime: form.playtime,
            estimatedPlaytime: form.estimatedPlaytime,
            console: form.console.console,
        });
        return response.data;
    } catch (error) {
        console.error("Error submitting game:", error);
        throw error;
    }
};

export default postGames;
