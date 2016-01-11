//
//  Base64Util.m
//  ThuInfo
//
//  Created by WenHao on 12-10-30.
//  Copyright (c) 2012年 WenHao. All rights reserved.
//

#import "Base64Util.h"

@implementation Base64Util
+ (NSString*)encodeBase64:(NSString*)input
{
    NSData *data = [input dataUsingEncoding:NSUTF8StringEncoding allowLossyConversion:YES];
    //转换到base64
    data = [GTMBase64 encodeData:data];
    NSString * base64String = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    return base64String;
}

+ (NSString*)decodeBase64:(NSString*)input
{
    NSData *data = [input dataUsingEncoding:NSUTF8StringEncoding allowLossyConversion:YES];
    //转换到base64
    data = [GTMBase64 decodeData:data];
    NSString * base64String = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    return base64String;
}
@end
