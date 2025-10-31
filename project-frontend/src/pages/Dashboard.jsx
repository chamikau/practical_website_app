import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "../styles/dashboard.css";

const Dashboard = () => {
    const [user, setUser] = useState(null);
    const [subscribedWebsites, setSubscribedWebsites] = useState([]);
    const [selectedWebsite, setSelectedWebsite] = useState(null);
    const [posts, setPosts] = useState([]);
    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");

    const navigate = useNavigate();

    useEffect(() => {
        const storedUser = localStorage.getItem("user_data");
        if (storedUser) {
            const parsedUser = JSON.parse(storedUser);
            setUser(parsedUser);
            if (parsedUser.id) fetchSubscribedWebsites(parsedUser.id);
        } else {
            navigate("/");
        }
    }, [navigate]);

    const fetchSubscribedWebsites = async (userId) => {
        try {
            const response = await axios.get(
                `http://localhost:8000/api/subscriber/${userId}/get-websites`
            );
            setSubscribedWebsites(response.data);
            if (response.data.length > 0) {
                const websiteId = response.data[0].pivot.website_id;
                setSelectedWebsite(websiteId);
                fetchPosts(websiteId);
            }
        } catch (error) {
            console.error("Error fetching subscribed websites:", error);
        }
    };

    const fetchPosts = async (websiteId) => {
        try {
            const response = await axios.get(
                `http://localhost:8000/api/websites/${websiteId}/get-posts`
            );
            setPosts(response.data.posts || []);
        } catch (error) {
            console.error("Error fetching posts:", error);
            setPosts([]);
        }
    };

    const handleCreatePost = async () => {
        if (!title || !description || !selectedWebsite) {
            alert("Please fill all fields");
            return;
        }
        try {
            const payload = { title, description, website_id: selectedWebsite };
            const response = await axios.post(
                `http://localhost:8000/api/websites/${selectedWebsite}/posts`,
                payload
            );
            alert(response.data.message || "Post created!");
            setTitle("");
            setDescription("");
            fetchPosts(selectedWebsite);
        } catch (error) {
            console.error(error);
            const errMsg = error.response?.data?.message || "Failed to create post";
            alert(errMsg);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem("user_data");
        navigate("/");
    };

    return (
        <div className="dashboard">
            <header className="dashboard-header">
                <h1 className="dashboard-title">
                    {selectedWebsite
                        ? subscribedWebsites.find((w) => w.id === selectedWebsite)?.name
                        : "Dashboard"}
                </h1>

                {user && (
                    <div className="header-right">
                        <div className="user-info">
                            <p>
                                <strong>Welcome, {user.name}</strong>
                            </p>
                            <p>Email: {user.email}</p>
                        </div>
                        <button className="btn-logout" onClick={handleLogout}>
                            Logout
                        </button>
                    </div>
                )}
            </header>

            <main className="main-content">
                <section className="create-post-card">
                    <h2>Create Post</h2>
                    <input
                        type="text"
                        placeholder="Post title"
                        value={title}
                        onChange={(e) => setTitle(e.target.value)}
                        className="input-field"
                    />
                    <textarea
                        placeholder="Post description"
                        value={description}
                        onChange={(e) => setDescription(e.target.value)}
                        className="input-field textarea-field"
                    />
                    <button className="btn-submit" onClick={handleCreatePost}>
                        Create Post
                    </button>
                </section>

                <section className="posts-section">
                    <h2>Posts</h2>
                    {posts.length > 0 ? (
                        <ul className="posts-list">
                            {posts.map((post) => (
                                <li key={post.id} className="post-card">
                                    <h3>{post.title}</h3>
                                    <p>{post.description}</p>
                                    <small>Website: {post.website?.name || "Unknown"}</small>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p>No posts available.</p>
                    )}
                </section>
            </main>
        </div>
    );
};

export default Dashboard;
