import React from "react";

const PostCard = ({ post }) => {
    return (
        <li className="post-card">
            <h3>{post.title}</h3>
            <p>{post.description}</p>
            <small>Website: {post.website?.name || "Unknown"}</small>
        </li>
    );
};

export default PostCard;
