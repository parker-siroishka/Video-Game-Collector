import axios from "axios";

const postGames = async (form) => {
    try {
        await axios.post(route("games.post"), {
            title: form.title,
            coverart: form.thumbnailUrl,
            playtime: form.playtime,
            estimatedPlaytime: form.estimatedPlaytime,
            console: form.console.console,
        });
        // Close modal on success
    } catch (error) {
        // Handle error
        console.error("Error submitting game:", error);
    }
};

export default postGames;
