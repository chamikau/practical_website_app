import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";

import InputField from "../../components/InputField";
import Button from "../../components/Button";


const LoginPage = () => {
    const [user, setUser] = useState({ email: "", password: "" });
    const navigate = useNavigate();

    const handleChange = (e) => {
        setUser({ ...user, [e.target.name]: e.target.value });
    };

    const loginUser = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post("http://localhost:8000/api/login", user);
            const data = response.data.user;

            if (data) localStorage.setItem("user_data", JSON.stringify(data));

            alert("Auth successful!");
            navigate("/dashboard");
        } catch (error) {
            console.error(error);
            const err = error.response?.data?.errors || error.response?.data?.message;
            alert(`Error: ${JSON.stringify(err)}`);
        }
    };

    return (
        <div className="auth-page"> {/* use auth-page for unified style */}
            <div className="auth-card"> {/* use auth-card */}
                <h1>Login</h1>
                <form onSubmit={loginUser}>
                    <InputField
                        type="email"
                        name="email"
                        value={user.email}
                        onChange={handleChange}
                        placeholder="Email"
                        required
                    />
                    <InputField
                        type="password"
                        name="password"
                        value={user.password}
                        onChange={handleChange}
                        placeholder="Password"
                        required
                    />
                    <Button type="submit" className="submit-btn">
                        Login
                    </Button>
                </form>

                <p className="auth-link">
                    Don’t have an account? <a href="/">Register here</a>
                </p>
            </div>
        </div>
    );
};

export default LoginPage;
