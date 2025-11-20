import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { registerUser, getWebsites } from "../../utils/api";
import InputField from "../../components/InputField";
import Button from "../../components/Button";
import MultiSelect from "../../components/MultiSelect";

const RegisterSubscribe = () => {
    const [user, setUser] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        website_ids: [],
    });

    const [websites, setWebsites] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        const loadWebsites = async () => {
            const res = await getWebsites();
            setWebsites(res.data.data);
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

    const handleRegister = async (e) => {
        e.preventDefault();
        if (user.password !== user.password_confirmation) {
            alert("Passwords do not match");
            return;
        }
        try {
            const res = await registerUser(user);
            localStorage.setItem("user_data", JSON.stringify(res.data.user));
            alert(res.data.message || "Registration Successful!");
            navigate("/dashboard");
        } catch (error) {
            alert(error.response?.data?.message || "Registration Failed");
        }
    };

    return (
        <div className="auth-page">
            <div className="auth-card">
                <h1>Register & Subscribe</h1>
                <form onSubmit={handleRegister}>
                    <InputField
                        type="text"
                        name="name"
                        value={user.name}
                        onChange={handleChange}
                        placeholder="Full Name"
                        required
                    />
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
                    <InputField
                        type="password"
                        name="password_confirmation"
                        value={user.password_confirmation}
                        onChange={handleChange}
                        placeholder="Confirm Password"
                        required
                    />

                    <label htmlFor="website_ids">Select Websites</label>
                    <MultiSelect
                        options={websites}
                        selectedValues={user.website_ids}
                        onChange={handleChange}
                    />

                    <Button type="submit" className="submit-btn">
                        Register & Subscribe
                    </Button>
                </form>
                <p className="auth-link">
                    Already have an account? <a href="/Login">Login here</a>
                </p>
            </div>
        </div>
    );
};

export default RegisterSubscribe;
