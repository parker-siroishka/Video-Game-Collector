import axios from "axios";

const postPlaySessions = async (sessionGame) => {
    try {
        await axios.post(route("playSessions.post"), {
            game_id: sessionGame.id,
            is_active: true,
            is_paused: false,
            notes: null,
            start_session: new Date().toISOString(),
        });
        showingCreateSessionModal.value = false;
        fetchGroupedPlaySessions();
    } catch (error) {
        console.error("Error starting session:", error);
    }
};

export default postPlaySessions;
