import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import InputField from "../../components/InputField";
import TextareaField from "../../components/TextareaField";
import Button from "../../components/Button";
import PostCard from "../../components/PostCard";

import {
    getSubscribedWebsites,
    getPostsByWebsite,
    createPost,
} from "../../utils/api";


const DashboardPage = () => {
    const [user, setUser] = useState(null);
    const [subscribedWebsites, setSubscribedWebsites] = useState([]);
    const [selectedWebsite, setSelectedWebsite] = useState(null);
    const [posts, setPosts] = useState([]);
    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");

    const navigate = useNavigate();

    useEffect(() => {
        const storedUser = localStorage.getItem("user_data");
        if (!storedUser) {
            navigate("/login");
            return;
        }

        const parsedUser = JSON.parse(storedUser);
        setUser(parsedUser);
        fetchWebsites(parsedUser.id);
    }, [navigate]);

    const fetchWebsites = async (userId) => {
        try {
            const response = await getSubscribedWebsites(userId);
            setSubscribedWebsites(response.data);

            if (response.data.length > 0) {
                const websiteId = response.data[0].pivot.website_id;
                setSelectedWebsite(websiteId);
                fetchPosts(websiteId);
            }
        } catch (err) {
            console.error("Failed to fetch websites:", err);
        }
    };

    const fetchPosts = async (websiteId) => {
        try {
            const response = await getPostsByWebsite(websiteId);
            setPosts(response.data.posts || []);
        } catch (err) {
            console.error("Failed to fetch posts:", err);
        }
    };

    const handleCreatePost = async () => {
        if (!title || !description || !selectedWebsite) {
            alert("Please fill all fields");
            return;
        }
        try {
            const payload = { title, description, website_id: selectedWebsite };
            await createPost(selectedWebsite, payload);
            alert("Post created successfully!");

            setTitle("");
            setDescription("");
            fetchPosts(selectedWebsite);
        } catch (err) {
            alert("Failed to create post");
            console.error(err);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem("user_data");
        navigate("/login");
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
                        <Button className="btn-logout" onClick={handleLogout}>
                            Logout
                        </Button>
                    </div>
                )}
            </header>

            <main className="main-content">
                <section className="create-post-card">
                    <h2>Create Post</h2>

                    <InputField
                        className="input-field"
                        value={title}
                        onChange={(e) => setTitle(e.target.value)}
                        placeholder="Post title"
                    />

                    <TextareaField
                        className="textarea-field"
                        value={description}
                        onChange={(e) => setDescription(e.target.value)}
                        placeholder="Post description"
                    />

                    <Button className="btn-submit" onClick={handleCreatePost}>
                        Create Post
                    </Button>
                </section>

                <section className="posts-section">
                    <h2>Posts</h2>

                    {posts.length > 0 ? (
                        <ul className="posts-list">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={{...post, website: subscribedWebsites.find(w => w.id === post.website_id)}}/>
                            ))}
                        </ul>
                    ) : (
                        <p>No posts found for this website.</p>
                    )}
                </section>
            </main>
        </div>
    );
};

export default DashboardPage;
