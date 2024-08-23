import axios from "axios";

const getGroupedPlaySessions = async () => {
    try {
        const { data } = await axios.get(route("playSessionsGrouped.get"));
        for (const [date, sessionsArray] of Object.entries(data)) {
            sessionsArray.sort((a, b) => {
                if (a.is_active && !b.is_active) {
                    return -1;
                }
                if (!a.is_active && b.is_active) {
                    return 1;
                }
                // If `is_active` status is the same, compare `created_at`
                return new Date(b.created_at) - new Date(a.created_at);
            });
        }
        return data;
    } catch (error) {
        console.error("Error fetching play sessions:", error);
    }
};

export default getGroupedPlaySessions;
