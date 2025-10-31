import React, { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "../styles/login.css";

const Login = () => {
    const [user, setUser] = useState({
        email: "",
        password: "",
    });
    const navigate = useNavigate();

    const handleChange = (e) => {
        setUser({
            ...user,
            [e.target.name]: e.target.value,
        });
    };

    const loginUser = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(
                "http://localhost:8000/api/login",
                user
            );

            const data = response.data.user;
            if (data) {
                localStorage.setItem("user_data", JSON.stringify(data));
            }
            alert(response.data.message || "Login successful!");
            navigate("/dashboard");
        } catch (error) {
            console.error(error);
            const err = error.response?.data?.errors || error.response?.data?.message;
            alert(`Error: ${JSON.stringify(err)}`);
        }
    };

    return (
        <div className="login-page">
            <div className="login-card">
                <h1>Login</h1>
                <form onSubmit={loginUser}>
                    <div className="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            value={user.email}
                            onChange={handleChange}
                            required
                        />
                    </div>

                    <div className="form-group">
                        <label>Password</label>
                        <input
                            type="password"
                            name="password"
                            value={user.password}
                            onChange={handleChange}
                            required
                        />
                    </div>

                    <button type="submit" className="submit-btn">
                        Login
                    </button>
                </form>

                <p className="register-link">
                    Don’t have an account? <a href="/">Register here</a>
                </p>
            </div>
        </div>
    );
};

export default Login;
