import axios from "axios";

const getWeeklyPlaytimeTotals = async (gameId) => {
    // gameId is passed as undefined from front end when All Games is selected
    // backend needs it as -1 to query all games
    let game_id = gameId ? gameId : -1;
    return await axios.get(route("playSessionsWeeklyTotals.get"), {
        params: {
            game_id,
        },
    });
};

export default getWeeklyPlaytimeTotals;
