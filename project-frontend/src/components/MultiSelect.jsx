import React from "react";

const MultiSelect = ({ options = [], selectedValues = [], onChange, className = "" }) => {
    return (
        <select
            name="website_ids"
            multiple
            value={selectedValues}
            onChange={onChange}
            className={`input-field ${className}`}
            required
        >
            {options.map((option) => (
                <option key={option.id} value={option.id}>
                    {option.name}
                </option>
            ))}
        </select>
    );
};

export default MultiSelect;
