import axios from "axios";

const getWeeklyPlaytimeTotals = async (gameId) => {
    return await axios.get(route("playSessionsWeeklyTotals.get"), {
        params: {
            game_id: gameId,
        },
    });
};

export default getWeeklyPlaytimeTotals;
