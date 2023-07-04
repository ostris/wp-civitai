import React from 'react';
import getComponentData from '../../utils/getComponentData';
import Model from '../../interfaces/Model';

const modelData = getComponentData('SingleModelPage') as Model | null;

const SingleModelPage = () => {
  if (!modelData) return null;
  return (
    <>
      <div dangerouslySetInnerHTML={{ __html: modelData.description }}></div>
    </>
  );
};

export default SingleModelPage;
