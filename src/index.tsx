import React from 'react';
import { render } from '@wordpress/element';
import './index.scss';
import SingleModelPage from './pages/SingleModelPage';

/**
 * This will dynamically load the component based on the id of the element.
 * if it is not found, it will not load the component.
 */

// Render the App component into the DOM
const targetComponentObj = {
  SingleModelPage: SingleModelPage,
};

for (let key in targetComponentObj) {
  const elem = document.getElementById(`wp-civitai-${key}`);
  // check if element exists
  if (elem) {
    const Component = targetComponentObj[key as keyof typeof targetComponentObj];
    render(<Component />, elem);
  }
}
