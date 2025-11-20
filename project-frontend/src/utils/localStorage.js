export const setUserData = (user) => localStorage.setItem("user_data", JSON.stringify(user));
export const getUserData = () => JSON.parse(localStorage.getItem("user_data"));
export const clearUserData = () => localStorage.removeItem("user_data");
