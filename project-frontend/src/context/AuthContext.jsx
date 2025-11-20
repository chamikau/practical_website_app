import { createContext, useState } from "react";
import { getUserData, setUserData, clearUserData } from "../utils/localStorage";

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(getUserData());

    const login = (userData) => {
        setUser(userData);
        setUserData(userData);
    };

    const logout = () => {
        setUser(null);
        clearUserData();
    };

    return (
        <AuthContext.Provider value={{ user, login, logout }}>
            {children}
        </AuthContext.Provider>
    );
};
