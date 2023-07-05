import React from 'react';
import getComponentData from '../../utils/getComponentData';
import Model from '../../interfaces/Model';
import { Carousel } from '@mantine/carousel';
import { Grid } from '@mantine/core';

const model = getComponentData('SingleModelPage') as Model | null;

const SingleModelPage = () => {
  const [selectedVersion, setSelectedVersion] = React.useState(0);
  // const { columnCount, columnWidth, maxSingleColumnWidth } = useMasonryContainerContext();
  if (!model) return null;
  const { columnCount, columnWidth, maxSingleColumnWidth } = {
    columnCount: 2,
    columnWidth: null,
    maxSingleColumnWidth: '33%',
  };
  const version = model.modelVersions[selectedVersion];
  const { data, itemId, height, extra } = {
    data: version.images,
    itemId: (item: any) => item.hash,
    height: 600,
    extra: null,
  };
  const totalItems = data.length;

  return (
    <>
      <Grid>
        <Grid.Col span={8}>
          <Carousel
            key={model.id}
            // classNames={classes}
            slideSize={`${100 / columnCount}%`}
            slideGap="md"
            align={totalItems <= columnCount ? 'start' : 'end'}
            withControls={totalItems > columnCount ? true : false}
            slidesToScroll={columnCount}
            // height={columnCount === 1 ? maxSingleColumnWidth : '100%'}
            loop
            sx={{
              width: columnCount === 1 ? maxSingleColumnWidth : '100%',
              maxWidth: '100%',
              margin: '0 auto',
              minHeight: 600,
            }}
          >
            {data.map((item, index) => {
              const key = itemId ? itemId(item) : index;
              return (
                <Carousel.Slide key={key} id={key.toString()}>
                  <div style={{ position: 'relative', paddingTop: '100%', height: height }}>
                    {/*{createRenderElement(RenderComponent, index, item, height)}*/}
                    <img src={item.url} alt={model.name} style={{ position: 'absolute', top: 0, left: 0 }} />
                  </div>
                </Carousel.Slide>
              );
            })}
            {extra && (
              <Carousel.Slide>
                <div style={{ position: 'relative', paddingTop: '100%' }}>{extra}</div>
              </Carousel.Slide>
            )}
          </Carousel>
        </Grid.Col>
        <Grid.Col span={4}>
          <h1>{model.name}</h1>
          <h2>{version.name}</h2>
          <p>{version.description}</p>
        </Grid.Col>
      </Grid>
      <div dangerouslySetInnerHTML={{ __html: model.description }}></div>
    </>
  );
};

export default SingleModelPage;
