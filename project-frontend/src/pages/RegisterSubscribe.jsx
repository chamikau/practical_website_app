import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import "../styles/register.css";

const RegisterSubscribe = () => {
    const navigate = useNavigate();

    const [user, setUser] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        website_ids: [],
    });

    const [websites, setWebsites] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadWebsites = async () => {
            try {
                const response = await axios.get("http://localhost:8000/api/websites");
                setWebsites(response.data);
            } catch (error) {
                console.error("Error loading websites:", error);
            } finally {
                setLoading(false);
            }
        };
        loadWebsites();
    }, []);

    const handleChange = (e) => {
        const { name, value, type, selectedOptions } = e.target;
        if (type === "select-multiple") {
            const values = Array.from(selectedOptions, (option) => option.value);
            setUser({ ...user, [name]: values });
        } else {
            setUser({ ...user, [name]: value });
        }
    };

    const registerUser = async (e) => {
        e.preventDefault();

        if (user.password !== user.password_confirmation) {
            alert("Passwords do not match");
            return;
        }

        try {
            const response = await axios.post(
                "http://localhost:8000/api/register-subscribe",
                user
            );

            const data = response.data.user;
            if (data) {
                localStorage.setItem("user_data", JSON.stringify(data));
            }

            alert(response.data.message || "Registration Successful!");
            navigate("/dashboard");
        } catch (error) {
            console.error(error);
            const errMsg = error.response?.data?.errors || error.response?.data?.message;
            alert(`Registration Failed: ${JSON.stringify(errMsg)}`);
        }
    };

    return (
        <div className="register-page">
            <div className="register-card">
                <h1>Register & Subscribe</h1>
                <p>Create a new account and subscribe to websites</p>

                {loading ? (
                    <p>Loading websites...</p>
                ) : (
                    <form onSubmit={registerUser}>
                        <div className="form-group">
                            <label htmlFor="name">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value={user.name}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label htmlFor="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value={user.email}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label htmlFor="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                value={user.password}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label htmlFor="password_confirmation">Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                value={user.password_confirmation}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label htmlFor="website_ids">Select Websites</label>
                            <select
                                id="website_ids"
                                name="website_ids"
                                multiple
                                value={user.website_ids}
                                onChange={handleChange}
                                required
                            >
                                {websites.map((website) => (
                                    <option key={website.id} value={website.id}>
                                        {website.name}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <button type="submit" className="submit-btn">
                            Register & Subscribe
                        </button>
                    </form>
                )}

                <p className="login-link">
                    Already have an account? <a href="/login">Login here</a>
                </p>
            </div>
        </div>
    );
};

export default RegisterSubscribe;
