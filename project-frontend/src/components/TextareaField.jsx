import React from "react";

const TextareaField = ({ value, onChange, placeholder, className = "" }) => {
    return (
        <textarea
            value={value}
            onChange={onChange}
            placeholder={placeholder}
            className={`input-field textarea-field ${className}`}
        />
    );
};

export default TextareaField;
