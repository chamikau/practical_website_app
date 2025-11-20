import React from "react";
import { render, screen, fireEvent } from "@testing-library/react";
import MultiSelect from "./MultiSelect";

describe("MultiSelect Component", () => {
    const options = [
        { id: 1, name: "Option 1" },
        { id: 2, name: "Option 2" },
        { id: 3, name: "Option 3" },
    ];

    test("renders all options", () => {
        render(<MultiSelect options={options} selectedValues={[]} onChange={() => {}} />);

        options.forEach(option => {
            expect(screen.getByText(option.name)).toBeInTheDocument();
        });
    });

    test("renders with selected values", () => {
        render(<MultiSelect options={options} selectedValues={[1, 3]} onChange={() => {}} />);

        const selectedOptions = screen.getAllByRole("option", { selected: true });
        expect(selectedOptions).toHaveLength(2);
        expect(selectedOptions[0].value).toBe("1");
        expect(selectedOptions[1].value).toBe("3");
    });

    test("calls onChange handler when selection changes", () => {
        const handleChange = jest.fn();
        render(<MultiSelect options={options} selectedValues={[]} onChange={handleChange} />);

        const selectElement = screen.getByRole("listbox"); // multiple select
        fireEvent.change(selectElement, { target: { value: "2" } });
        expect(handleChange).toHaveBeenCalledTimes(1);
    });
});
